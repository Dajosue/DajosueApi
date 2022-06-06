<?php

namespace App\Controllers;
header('Access-Control-Allow-Origin: *');

use CodeIgniter\RESTful\ResourceController;

class RestUsuarios extends ResourceController
{
    protected $modelName = "App\Models\UsuarioModel";
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

    public function Login($id = null)
    {
        $data = $this->model->where('n_empleado',$id)->first();
        if(is_null($data)) {
            return $this->respond(['response' =>['type' => 'error','msg' => 'El trabajador no existe']], 201);
            //return $this->fail(['error' => 'Project does not exist'], 404);
        }
        $data['log'] = true;
 
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
            'id_usuario' => $this->request->getPost('id_usuario'),
            'n_empleado' => $this->request->getPost('n_empleado'),
            'nombre' => $this->request->getPost('nombre'),
            'id_permiso' => $this->request->getPost('id_permiso'),
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
            'id_usuario' => $this->request->getVar('id_usuario'),
            'n_empleado' => $this->request->getVar('n_empleado'),
            'nombre' => $this->request->getVar('nombre'),
            'id_permiso' => $this->request->getVar('id_permiso'),
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
