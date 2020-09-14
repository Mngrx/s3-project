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

class ObjectController extends Controller
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
    public function index($id)
    {

        $bucket = Bucket::where('id', $id)->first();

        $objects = Obj::where('bucket_id', $id)->get();

        return view('object.index', ['objects' => $objects, 'bucket' => $bucket]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {

        $bucket = Bucket::where('id', $id)->first();
        return view('object.create', ['bucket' => $bucket]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'file' => 'required|file'
        ]);


        $bucket = Bucket::where('id', $id)->first();

        $source = $request->file;
        $request->name = strtolower($request->name). '.' . $request->file->extension();

        do {
            try {
                $result = $this->s3->putObject([
                    'Bucket' => $bucket->name,
                    'Key'    => $request->name,
                    'SourceFile' => $source
                ]);

                if ($result["@metadata"]["statusCode"] == '200') {

                    $object = new Obj;

                    $object->bucket_id = $id;
                    $object->name = $request->name;
                    $object->link = $result["ObjectURL"];
                    $object->created_by = Auth::id();

                    $object->save();

                    return Redirect::to('/buckets/' . $id . '/objects');
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
    public function show($id, $cod)
    {
        $bucket = Bucket::where('id', $id)->first();
        $object = Obj::where('id', $cod)->first();

        try {
            $result = $this->s3->getObject([
                'Bucket' => $bucket->name,
                'Key'    => $object->name
            ]);

            header("Content-Type: {$result['ContentType']}");
            header('Content-Disposition: attachment; filename='.$object->name);
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
    public function destroy($id, $cod)
    {
        $bucket = Bucket::where('id', $id)->first();
        $object = Obj::where('id', $cod)->first();


        try {

            $result = $this->s3->deleteObject([
                'Bucket' => $bucket->name,
                'Key' => $object->name,
            ]);


            $object->delete();

            return Redirect::to('/buckets/' . $id . '/objects');
        } catch (S3Exception $e) {
            return $e->getMessage();
        }
    }
}
