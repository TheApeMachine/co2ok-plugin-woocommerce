export default class Conditional {

  has_classes(e, classes) {
    return classes.length != classes.filter(n => !jQuery(e.target).includes(n)).length;
  }

}
