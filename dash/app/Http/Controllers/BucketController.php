<?php

namespace App\Http\Controllers;

use App\Models\Bucket;
use Aws\Credentials\Credentials;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BucketController extends Controller
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

        $buckets = Bucket::all();

        return view('bucket.index', ['buckets' => $buckets]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bucket.create');
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
            'bucket' => 'required|max:255',
            'description' => ''
        ]);

        // return $request->all();
        DB::beginTransaction();
        try {

            $request->bucket = strtolower($request->bucket);

            $result = $this->s3->createBucket([
                'Bucket' => $request->bucket,
            ]);

            $bucket = new Bucket;

            $bucket->name = $request->bucket;
            $bucket->description = $request->description;
            $bucket->created_by = Auth::id();

            $bucket->save();

            DB::commit();

            return Redirect::to('/buckets');

        } catch (AwsException $e) {
            DB::rollBack();
            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        $bucket = Bucket::where('id', $id)->first();

        try {
            $objects = $this->s3->getIterator('ListObjects', ([
                'Bucket' => $bucket->name
            ]));

            foreach ($objects as $object) {
                $result = $this->s3->deleteObject([
                    'Bucket' => $bucket->name,
                    'Key' => $object['Key'],
                ]);
            }

            $result = $this->s3->deleteBucket([
                'Bucket' => $bucket->name,
            ]);

            $bucket->delete();

            return Redirect::to('/buckets');

        } catch (S3Exception $e) {
            return $e->getMessage();
        }
    }
}
