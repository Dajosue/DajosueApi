<?php

namespace App\Controllers;

header('Access-Control-Allow-Origin: *');
header('content-type: application/json; charset=utf-8');

use CodeIgniter\RESTful\ResourceController;

class RestMesas extends ResourceController
{
    protected $modelName = "App\Models\MesasModel";
    protected $format = "json";
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        return $this->respond($this->model->findAll(), 200);
    }
    
    public function getMesasAbiertas()
    {
        return $this->respond($this->model->where('estado', 1)->findAll(), 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->model->find($id);
        if (is_null($data) || $id == 0) {
            return $this->respond(['response' => ['type' => 'warning', 'msg' => 'Error: Registre la mesa']], 200);
            //return $this->fail(['error' => 'Project does not exist'], 404);
        }

        return $this->respond($data, 200);
    }


    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = [
            'id_mesa'  => $this->request->getPost('id_mesa'),
            'num_mesa' => $this->request->getPost('num_mesa'),
            'estado'  => $this->request->getPost('estado'),
        ];

        if ($this->model->insert($data) === false) {

            $response = [
                'errors' => $this->model->errors(),
                'message' => 'Invalid Inputs'
            ];

            return $this->fail($response, 409);
        }

        return $this->respond(['message' => 'Created Successfully'], 201);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $jsonRecived = $this->request->getJSON();
        
        $array = json_decode(json_encode($jsonRecived), true);

        $data = [
            'id_mesa' => isset($array['id_mesa']) ? $array['id_mesa'] : $id,
            'estado' => $array['estado'],
        ];

        //dd(print_r($this->request->getJSON()), print_r($this->request->getPost()), print_r($this->request->getVar()));

        foreach ($data as $key => $val) {

            if ($data[$key] == null) {

                unset($data[$key]);
            }
        }

        if ($this->model->where('id_mesa', $id)->set($data)->update() === false) {
            $response = [
                'errors' => $this->model->errors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->fail($response, 409);
        }

        return $this->respond(['message' => 'Updated Successfully'], 200);
    }
    
    public function updateMesa($id = null)
    {
        $array = json_decode(json_encode($this->request->getJSON()), true);


        $data = [
            'estado' => $array['estado'],
        ];

        //dd(print_r($this->request->getJSON()), print_r($this->request->getPost()), print_r($this->request->getVar()));

        if ($this->model->where('id_mesa', $id)->set($data)->update() === false) {
            $response = [
                'errors' => $this->model->errors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->fail($response, 409);
        }

        return $this->respond(['message' => 'Updated Successfully'], 200);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $this->model->delete($id);
        return $this->respond(['message' => 'Deleted Successfully'], 200);
    }
}
