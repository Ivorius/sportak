<?php

namespace App\Forms;

use Kdyby;
use Nette;

class EntityForm extends Nette\Application\UI\Form
{
        use Kdyby\DoctrineForms\EntityForm;
}

interface IEntityFormFactory
{
        /** @return EntityForm */
        function create();
}