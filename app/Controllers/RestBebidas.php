<?php

namespace App\Controllers;
header('Access-Control-Allow-Origin: *');

use CodeIgniter\RESTful\ResourceController;

class RestBebidas extends ResourceController
{
    protected $modelName = "App\Models\BebidasModel";
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

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->model->find($id);
        if (is_null($data)) {
            return $this->fail(['error' => 'Project does not exist'], 404);
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
            'id_bebida' => $this->request->getPost('id_bebida'),
            'nombre' => $this->request->getPost('nombre'),
            'precio' => $this->request->getPost('precio'),
            'id_tipo_de_bebida' => $this->request->getPost('id_tipo_de_bebida'),
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
        $data = [
            'id_bebida' => $this->request->getVar('id_bebida'),
            'nombre' => $this->request->getVar('nombre'),
            'precio' => $this->request->getVar('precio'),
            'id_tipo_de_bebida' => $this->request->getVar('id_tipo_de_bebida'),
        ];

        if ($this->model->where('id', $id)->set($data)->update() === false) {
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
