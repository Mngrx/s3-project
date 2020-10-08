<?php

namespace App\Http\Controllers;

use App\Models\Bucket;
use App\Models\Obj;
use Aws\Credentials\Credentials;
use Aws\Exception\MultipartUploadException;
use Aws\S3\Exception\S3Exception;
use Aws\S3\Exception\S3MultipartUploadException;
use Aws\S3\MultipartUploader;
use Aws\S3\ObjectUploader;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CatController extends Controller
{
    private $s3;

    public function __construct()
    {

        $credentials = new Credentials(config('aws.credentials.key'), config('aws.credentials.secret'));

        $this->s3  = new S3Client([
            'region' => 'us-east-2',
            'version' => 'latest',
            'use_path_style_endpoint' => true,
            'credentials' => $credentials
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bucket = 'just-cats-apenas';
        $objects = $this->s3->listObjects([
            'Bucket' => $bucket
        ]);

        // return $objects;
        return view('cat.index', ['objects' => $objects['Contents']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bucket = 'just-cats-apenas';
        return view('cat.create', ['bucket' => $bucket]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'file' => 'required|file'
        ]);


        $bucket = 'just-cats-apenas';

        $source = $request->file;
        $request->name = strtolower($request->name). '.' . $request->file->extension();

        do {
            try {
                $result = $this->s3->putObject([
                    'Bucket' => $bucket,
                    'Key'    => str_replace(' ', '_', $request->name),
                    'SourceFile' => $source
                ]);

                if ($result["@metadata"]["statusCode"] == '200') {
                    return Redirect::to('/cat');
                }
                print($result);
            } catch (MultipartUploadException $e) {
                rewind($source);
                $uploader = new MultipartUploader($this->s3, $source, [
                    'state' => $e->getState(),
                ]);
            }
        } while (!isset($result));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($cod)
    {
        $bucket = 'just-cats-apenas';
        $object = $cod;

        try {
            $result = $this->s3->getObject([
                'Bucket' => $bucket,
                'Key'    => $object
            ]);

            header("Content-Type: {$result['ContentType']}");
            header('Content-Disposition: attachment; filename='.$object);
            echo $result['Body'];
        } catch (S3Exception $e) {
            return $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($cod)
    {
        $bucket = 'just-cats-apenas';
        $object = $cod;

        try {

            $result = $this->s3->deleteObject([
                'Bucket' => $bucket,
                'Key' => $object,
            ]);

            return Redirect::to('/cat');
        } catch (S3Exception $e) {
            return $e->getMessage();
        }
    }
}
