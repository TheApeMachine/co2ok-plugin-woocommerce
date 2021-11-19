export default class Conditional {

  has_classes(e, classes) {
    return len(classes) != len(
      classes.filter(n => !jQuery(e.target).includes(n))
    );
  }

}
