<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use DateTime;
use Date;
use response;
use Illuminate\Support\Facades\Input;

class apiController extends Controller
{
    public function getIndex()
    {
        $data = array(
            'options' => [
                'comics','events','series','stories'
            ],
            'error' => null
        );
        return view('main')->with($data);
    }

    public function postIndex()
    {
        $data = array(
            'options' => [
                'comics','events','series','stories'
            ],
            'error' => null,
            'response' => Input::all()
        );
        if ( !empty($data['response']['character'])
            && !empty($data['response']['type'] )) {

            $url  = 'gateway.marvel.com/v1/public/characters?name=' .self::cleanString($data['response']['character']);
            $url .= self::urlParams();
            $character = self::request($url);

            if($character){
                $character = $character[0];
                $url = self::selectedUrl($character,$data);
                $url .= self::urlParams();

                $data['result'] = self::request($url);


                if($data['result'] ){
                    return self::exportToCsv($data);
                } else {
                    $data['error'] = 'Looks like we could not data on this search';
                }

            } else {
                $data['error'] = 'Looks like we could not find this character';
            }

        } else {

           $data['error'] = 'Looks like you forgot to enter something';
        }

        return view('main')->with($data);
    }

    private function exportToCsv($data){

        $filename = "Marvel-API.csv";
        $handle = fopen($filename, 'w+');

        fputcsv($handle, array('Character', 'Date Type', 'Name', 'Description', 'Date First Published'));
        foreach($data['result'] as $row) {
            switch ($data['response']['type']) {
                case 'comics':

                    fputcsv($handle, array(
                        (string)$data['response']['character'],
                        (string)$data['response']['type'],
                        (string)$row->title,
                        (string)$row->description,
                        (string)$row->dates[0]->date));
                        break;
                case 'events':
                    fputcsv($handle, array(
                        (string)$data['response']['character'],
                        (string)$data['response']['type'],
                        (string)$row->title,
                        (string)$row->description,
                        (string)$row->start));
                        break;

                case 'series':
                    fputcsv($handle, array(
                        (string)$data['response']['character'],
                        (string)$data['response']['type'],
                        (string)$row->title,
                        (string)$row->description,
                        (string)$row->startYear));
                        break;

                case 'stories':
                    fputcsv($handle, array(
                        (string)$data['response']['character'],
                        (string)$data['response']['type'],
                        (string)$row->title,
                        (string)$row->description,
                        (string)$row->modified));
                        break;
            }

        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return response()->download($filename, 'file '.date("d-m-Y H:i").'.csv', $headers);
    }

    private function selectedUrl($character, $data) {
        $url  = 'gateway.marvel.com/v1/public/';
        $url .= $data['response']['type'];
        $url .= '?characters='. $character->id;

        switch ($data['response']['type']){
            case 'comics':
                $url .= '&orderBy=onsaleDate';
                break;
            case 'events':
                $url .= '&orderBy=startDate';
                break;
            case 'series':
                $url .= '&orderBy=startYear';
                break;
            case 'stories':
                $url .= '&orderBy=modified';
                break;
        }
        $url .= '&limit=40';

        return $url;
    }

    private function request($url) {

        $client = new Client();

        try {

            $request = $client->request('GET', $url);
            $request = json_decode($request->getBody());

            if(!$request){

                return false;
            }

            $request = $request->data->results;

            return $request;

        } catch (\Exception $e) {

            return false;
        }
        return false;
    }


    private function urlParams(){

        $date = new DateTime()
        ;
        $requestData = array(
            'timeStamp' => $date->getTimestamp(),
            'apiKey' => '098efb6718df07440ed87e831eced367',
            'privateKey' => 'c955fc074213efc5e4e5e4bb803bc0f9915a7acc',
        );

        $hash = hash('md5',(
            $requestData['timeStamp']
            .$requestData['privateKey']
            .$requestData['apiKey'])
        );

        $url = '&ts='.$requestData['timeStamp'];
        $url .= '&apikey='.$requestData['apiKey'];
        $url .= '&hash='.$hash;

        return $url;
    }

    private function cleanString($string) {

        //Trim
        $string = trim($string);

        //Lower case everything
        $string = strtolower($string);

        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);

        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);

        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);

        return $string;
    }

}
