export default class Conditional {

  has_classes(e, classes) {
    // Return the comparison between the length of the class list you pass in and that
    // same class list, filtered by the element's classes. If the element has one or more
    // matching classes they will be filtered and so they original and filtered will not
    // be the same size, and thus: return true.
    return classes.length != classes.filter(n => !e.target.className.includes(n)).length;
  }

}
