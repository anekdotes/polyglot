<?php

namespace app;

use Anekdotes\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Abstract model that eases the localization of Illuminate model.
 */
class TranslatedModel extends Model
{
  /**
   * An array of polyglot attributes.
   *
   * @var array
   */
  protected $polyglot = [];

  /**
   * The "booting" method of the model.
   */
  protected static function boot()
  {
      parent::boot();
      static::saving(function ($model) {

          // Cancel if not localized
          $hasPolyglotAttributes = $model->getPolyglotAttributes();
          $hasPolyglotAttributes = empty($hasPolyglotAttributes);
          if ($hasPolyglotAttributes) {
              return true;
          }

          // Get the model's attributes
          $attributes = $model->getAttributes();
          $translated = [];

          // Extract polyglot attributes
          foreach ($attributes as $key => $value) {
              if (in_array($key, $model->getPolyglotAttributes())) {
                  unset($attributes[$key]);
                  $translated[$key] = $value;
              }
          }

          // If no localized attributes, continue
          if (empty($translated)) {
              return true;
          }

          // Get the current lang and Lang model
          $lang = Arr::get($translated, 'locale', App::getLocale());
          $langModel = $model->$lang;
          $translated['locale'] = $lang;

          // Save original model
          $model = $model->newInstance($attributes, $model->exists);
          $model->save();

          // If no Lang model, create one
          if (!$langModel) {
              $langModel = $model->getLangClass();
              $langModel = new $langModel($translated);
              $model->translations()->save($langModel);
          }

          $langModel->fill($translated)->save();

          return false;
      });
  }

  ////////////////////////////////////////////////////////////////////
  //////////////////////////// RELATIONSHIPS /////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Reroutes functions to the language in use.
   *
   * @param  string  $lang A language to use
   *
   * @return Collection Translated version of the model in the requested locale
   */
  public function lang($lang = null)
  {
      if (!$lang) {
          $lang = App::getLocale();
      }

      return $this->$lang();
  }

  /**
   * Get all translations.
   *
   * @return Collection a Collection of all the translations of the model
   */
  public function translations()
  {
      return $this->hasMany($this->getLangClass(), 'id');
  }

  /**
   * Get the french translation of the model.
   *
   * @return Collection The french translation of the model
   */
  public function fr()
  {
      return $this->hasOne($this->getLangClass(), 'id')->where('locale', '=', 'fr');
  }

  /**
   * Get the english translation of the model.
   *
   * @return Collection The english translation of the model
   */
  public function en()
  {
      return $this->hasOne($this->getLangClass(), 'id')->where('locale', '=', 'en');
  }

  /**
   * Get the spanish translation of the model.
   *
   * @return Collection The spanish translation of the model
   */
  public function es()
  {
      return $this->hasOne($this->getLangClass(), 'id')->where('locale', '=', 'es');
  }

  ////////////////////////////////////////////////////////////////////
  ////////////////////////////// ATTRIBUTES //////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Get the polyglot attributes.
   *
   * @return array Polyglot attributes
   */
  public function getPolyglotAttributes()
  {
      return array_merge($this->polyglot, ['locale']);
  }

  /**
   * Checks if a field isset while taking into account localized attributes.
   *
   * @param string $key The key
   *
   * @return bool If the translated field is set
   */
  public function __isset($key)
  {
      if (in_array($key, $this->getPolyglotAttributes())) {
          return true;
      }

      return parent::__isset($key);
  }

  /**
   * Get a localized attribute.
   *
   * @param string $key The attribute
   *
   * @return mixed The localized version of the attribute
   */
  public function __get($key)
  {
      // If the attribute is set to be automatically localized
      if (in_array($key, $this->polyglot)) {
          $lang = App::getLocale();

          return $this->$lang ? $this->$lang->$key : null;
      }

      return parent::__get($key);
  }

  ////////////////////////////////////////////////////////////////////
  /////////////////////////// PUBLIC HELPERS /////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Localize a model with an array of lang arrays.
   *
   * @param  array $localization An array in the form [field][lang][value]
   */
  public function localize($localization)
  {
      if (!$localization) {
          return false;
      }

      $langs = array_keys($localization[key($localization)]);

      // Build lang arrays
      foreach ($localization as $key => $value) {
          foreach ($langs as $lang) {
              ${$lang}[$key] = array_get($value, $lang);
              ${$lang}['locale'] = $lang;
          }
      }

      // Update
      $class = $this->getLangClass();
      foreach ($langs as $lang) {
          if (!is_object($this->$lang)) {
              $class = new $class($$lang);
              $this->$lang()->insert($class);
          } else {
              $this->$lang->fill($$lang);
              $this->$lang->save();
          }
      }
  }

  /**
   * Localize a "with" method.
   *
   * @param Query  $query      The Query item to use with on
   * @param string $relations  The relation with is used with
   *
   * @return Query The With method on the Query
   */
  public function scopeWithLang()
  {
      $relations = func_get_args();
      $query = array_shift($relations);

      if (empty($relations)) {
          $relations = [App::getLocale()];
      }

      // Localize
      //$eager = call_user_func_array(array(App::make('polyglot.lang'), 'eager'), $relations);

      return $query->with($relations[0]);
  }

  ////////////////////////////////////////////////////////////////////
  //////////////////////////////// HELPERS ///////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Get the Lang class corresponding to the current model.
   *
   * @return string the name of the Lang class
   */
  public function getLangClass()
  {
      // Get class name
      $model = get_called_class();
      $model = explode('\\', $model);
      $model[sizeof($model) - 1] = end($model).'Lang';

      return implode('\\', $model);
  }
}
