<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of IWorkflow
 *
 * @author chrigarc
 */
interface IWorkflow
{
    public function view_new($id_workflow);
}
