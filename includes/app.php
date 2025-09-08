<?php

require 'funciones.php';
require 'database.php';
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/ActiveRecord.php';

// Conectarnos a la base de datos
use Models\ActiveRecord;

ActiveRecord::setDB($db);
