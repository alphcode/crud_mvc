<?php

namespace App\Controller;

use App\Model\HomeModel;
use EasyProjects\SimpleRouter\Router;

class HomeController
{
    public function index(){

        $contacts = new HomeModel();
        $data['contacts'] = $contacts->getAllContacts();
        view('home',$data);
    }

    public function create(){
        view('create');
    }

    public function store(){

        $client = (array)Router::$request->body;
        $url_img_profile = saveFile((array)Router::$request->files->file,'assets/img/',["jpg", "jpeg", "png", "gif", "PNG", "JPG", "JPEG"]);
        $HomeModel = new HomeModel();
        ($HomeModel->insertContact($client,$url_img_profile)) ? Router::$response->status(200)->send(['data' => 'It was inserted the contact successfully'])
                                                                : Router::$response->status(400)->send(['data' => 'Error inserting data']);
    }

    public function destroy(){

        $id_contact = Router::$request->params->id;
        $homeModel = new HomeModel();
        $img = $homeModel->getContactImg($id_contact)[0]['coct_url_img_profile'];

        if ($homeModel->deleteContact($id_contact)){
            if(!empty($img)){
                unlink('assets/img/'.$img);
            }
            Router::$response->status(200)->send(['data' => 'The contact was deleted successfully']);
        }
        Router::$response->status(400)->send(['Error deleting the contact']);
    }

    public function edit(){

        $data['id_contact'] = Router::$request->params->id;

        $homeModel = new HomeModel();
        $data['contact'] = $homeModel->getContact(Router::$request->params->id);
        view('edit',$data);
    }

    public function update(){

        $id_client = Router::$request->params->id;
        $contact = (array)Router::$request->body;

        $homeModel = new HomeModel();

        ($homeModel->editCliente($contact,$id_client)) ? Router::$response->status(200)->send(['data' => 'It was inserted the contact successfully'])
            : Router::$response->status(400)->send(['data' => 'Error inserting data']);
    }

}