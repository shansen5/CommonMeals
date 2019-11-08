<?php

$meal = Utils::getMealByGetId();

$dao = new MealDao();
$dao->delete($meal->getId());
Flash::addFlash('Meal deleted successfully.');

Utils::redirect('meal-list', array());
