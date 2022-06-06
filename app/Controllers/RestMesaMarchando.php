<?php

namespace App\Controllers;

header('Access-Control-Allow-Origin: *');

use CodeIgniter\RESTful\ResourceController;

class RestMesaMarchando extends ResourceController
{
    protected $modelName = "App\Models\MesaMarchandoModel";
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

    public function showList($id = null)
    {
        $data = $this->model->where('id_mesa', $id)->findAll();
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
        $data = json_decode(json_encode($this->request->getJSON()), true);

        $id_mesaSave = $data[0]['id_mesa'];

        $this->model->where('id_mesa', $id_mesaSave)->delete();

        foreach ($data as $key => $value) {

            $nombre = isset($value['nombre']) ?  $value['nombre'] : $value['nombre_product'];
            $id_mesa = $id_mesaSave;
            $precio = $value['precio'];

            $data = [
                'nombre_product' => $nombre,
                'id_mesa' => $id_mesa,
                'precio' => $precio
            ];

            if ($this->model->insert($data) === false) {

                $response = [
                    'errors' => $this->model->errors(),
                    'message' => 'Invalid Inputs'
                ];

                return $this->fail($response, 409);
            }

        }
        return $this->respond(['message' => 'Created Successfully'], 201);

        /*
        $data = [
            'id_mesa_marchando' => $this->request->getPost('id_mesa_marchando'),
            'id_mesa' => $this->request->getPost('id_mesa'),
            'nombre_product' => $this->request->getPost('nombre_product'),
            'precio' => $this->request->getPost('precio'),
        ];

        if ($this->model->insert($data) === false) {

            $response = [
                'errors' => $this->model->errors(),
                'message' => 'Invalid Inputs'
            ];

            return $this->fail($response, 409);
        }

        return $this->respond(['message' => 'Created Successfully'], 201);
         */
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $data = [
            'id_mesa_marchando' => $this->request->getVar('id_mesa_marchando'),
            'id_mesa' => $this->request->getVar('id_mesa'),
            'nombre_product' => $this->request->getVar('nombre_product'),
            'precio' => $this->request->getVar('precio'),
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
        $this->model->where('id_mesa', $id)->delete();
        return $this->respond(['message' => 'Deleted Successfully'], 200);
    }
}
