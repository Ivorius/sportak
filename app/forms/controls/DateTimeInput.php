<?php

namespace App\Forms\Controls;

use Nette;

class DateTimeInput extends Nette\Forms\Controls\BaseControl {

	/** @var \DateTimeInterface */
	private $date;

	public function setValue($value = NULL) {
		if ($value === NULL) {
			$this->date = NULL;
		} elseif ($value instanceof \DateTimeInterface) {
			$this->date = $value;
		} else {
			throw new \InvalidArgumentException();
		}

		return $this;
	}

	public function getValue() {
		return $this->date;
	}

	public function loadHttpData() {
		$this->date = Nette\Utils\DateTime::from($this->getHttpData(Nette\Forms\Form::DATA_LINE));
	}

	public function getControl() {
		$control = Nette\Utils\Html::el('input', [
					'type' => 'datetime-local',
					'name' => $this->getHtmlName(),
					'value' => $this->date !== NULL ? $this->date->format('d.m.Y') : NULL,
					'disabled' => $this->isDisabled(),
		]);

		return $control;
	}

	public function isFilled() {
		return $this->date !== NULL;
	}

}
