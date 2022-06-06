<?php

namespace App\Controllers;
header('Access-Control-Allow-Origin: *');

use CodeIgniter\RESTful\ResourceController;

class RestSalaRestaurante extends ResourceController
{
    protected $modelName = "App\Models\SalaRestauranteModel";
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
        if(is_null($data)) {
            return $this->fail(['error' => 'Project does not exist'], 404);
        }
 
        return $this->respond($data,200);
    }
 
     
    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = [
            'id_sala_restaurante' => $this->request->getPost('id_sala_restaurante'),
            'num_mesa' => $this->request->getPost('num_mesa'),
        ];
 
        if ($this->model->insert($data) === false){
             
            $response = [
                'errors' => $this->model->errors(),
                'message' => 'Invalid Inputs'
            ];
 
            return $this->fail($response , 409);
        }
         
        return $this->respond(['message' => 'Created Successfully'],201);
    }
 
    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $data = [
            'id_sala_restaurante' => $this->request->getVar('id_sala_restaurante'),
            'num_mesa' => $this->request->getVar('num_mesa'),
        ];
  
        if ($this->model->where('id', $id)->set($data)->update() === false)
        {
            $response = [
                'errors' => $this->model->errors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->fail($response , 409);
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
