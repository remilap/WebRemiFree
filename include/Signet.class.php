<?

class Signet {
  protected $name;      // string
  protected $url;       // string
  protected $icon;      // string
  protected $category;  // string
  protected $target;    // string

  public function __construct($name, $url, $category, $target="new", $icon="default.ico") {
    $this->setName($name);
    $this->setUrl($url);
    $this->setCategory($category);
    $this->setIcon($icon);
    $this->setTarget($target);
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }

  public function setUrl($url) {
    $this->url = $url;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setCategory($category) {
    $this->category = $category;
  }

  public function getCategory() {
    return $this->category;
  }

  public function setIcon($icon) {
    $this->icon = $icon;
  }

  public function getIcon() {
    return $this->icon;
  }

  public function setTarget($target) {
    $this->target = $target;
  }

  public function getTarget() {
    return $this->target;
  }

  public function getSignetFromDatabase($db) {
    //
  }

  public function addSignetToDatabase($db) {
    //
  }

}

?>
