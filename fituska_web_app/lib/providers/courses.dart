import 'package:fituska_web_app/models/message.dart';
import 'package:fituska_web_app/models/thread.dart';
import 'package:fituska_web_app/models/user.dart';
import 'package:flutter/material.dart';

import '../models/course.dart';

class Courses with ChangeNotifier {
  List<Course> _courses = [
    Course(
        id: 1,
        name: "Course1",
        teacher: 1,
        approvedBy: 3,
        created: DateTime.now(),
        approved: DateTime.now(),
        threads: [
          Thread(
              id: 1,
              title: "Thread 1 kámo",
              author: 2,
              category: "Kokoti",
              messages: [
                Message(
                    text: "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Fusce wisi. Et harum quidem rerum facilis est et expedita distinctio. Fusce dui leo, imperdiet in, aliquam sit amet, feugiat eu, orci. Aliquam erat volutpat. Praesent id justo in neque elementum ultrices. Suspendisse sagittis ultrices augue. Curabitur bibendum justo non orci. Aenean id metus id velit ullamcorper pulvinar. Fusce dui leo, imperdiet in, aliquam sit amet, feugiat eu, orci. Maecenas aliquet accumsan leo. Nam quis nulla. Sed vel lectus. Donec odio tempus molestie, porttitor ut, iaculis quis, sem. Duis risus. Aliquam id dolor.Nulla pulvinar eleifend sem. Vestibulum fermentum tortor id mi. Aenean id metus id velit ullamcorper pulvinar. Fusce tellus odio, dapibus id fermentum quis, suscipit id erat. Vivamus luctus egestas leo. Maecenas aliquet accumsan leo. Quisque porta. Sed vel lectus. Donec odio tempus molestie, porttitor ut, iaculis quis, sem. Integer rutrum, orci vestibulum ullamcorper ultricies, lacus quam ultricies odio, vitae placerat pede sem sit amet enim. Proin mattis lacinia justo. Mauris dictum facilisis augue.\n\n Etiam bibendum elit eget erat. Cras pede libero, dapibus nec, pretium sit amet, tempor quis. Nulla non arcu lacinia neque faucibus fringilla. Maecenas fermentum, sem in pharetra pellentesque, velit turpis volutpat ante, in pharetra metus odio a lectus. Proin pede metus, vulputate nec, fermentum fringilla, vehicula vitae, justo.",
                    role: "Student",
                    author: 3),
                Message(
                    text: "Ahoj hele, vole kde mám káru1",
                    role: "Student1",
                    author: 2),
                Message(
                    text: "Ahoj hele, vole kde mám káru2",
                    role: "Student2",
                    author: 3),
                Message(
                    text: "Ahoj hele, vole kde mám káru3",
                    role: "Student3",
                    author: 1),
              ]),
          Thread(
              id: 1,
              author: 1,
              title: "Thread 2 kámo",
              category: "Kokoti",
              messages: [
                Message(
                    text: "Ahoj hele, vole kde mám káru2",
                    role: "Student",
                    author: 2),
                Message(
                    text: "Ahoj hele, vole kde mám káru21",
                    role: "Student1",
                    author: 3),
                Message(
                    text: "Ahoj hele, vole kde mám káru22",
                    role: "Student2",
                    author: 2),
                Message(
                    text: "Ahoj hele, vole kde mám káru23",
                    role: "Student3",
                    author: 1),
              ])
        ]),
    Course(
        id: 3,
        name: "Course2",
        teacher: 1,
        approvedBy: 2,
        created: DateTime.now(),
        approved: DateTime.now(),
        threads: [
          Thread(
              id: 1,
              author: 2,
              title: "Thread 1 kámo",
              category: "Kokoti",
              messages: [
                Message(
                    text: "Ahoj hele, vole kde mám káru",
                    role: "Student",
                    author: 3),
                Message(
                    text: "Ahoj hele, vole kde mám káru1",
                    role: "Student1",
                    author: 1),
                Message(
                    text: "Ahoj hele, vole kde mám káru2",
                    role: "Student2",
                    author: 3),
                Message(
                    text: "Ahoj hele, vole kde mám káru3",
                    role: "Student3",
                    author: 2),
              ]),
          Thread(
              id: 1,
              author: 1,
              title: "Thread 2 kámo",
              category: "Kokoti",
              messages: [
                Message(
                    text: "Ahoj hele, vole kde mám káru2",
                    role: "Student",
                    author: 3),
                Message(
                    text: "Ahoj hele, vole kde mám káru21",
                    role: "Student1",
                    author: 2),
                Message(
                    text: "Ahoj hele, vole kde mám káru22",
                    role: "Student2",
                    author: 1),
                Message(
                    text: "Ahoj hele, vole kde mám káru23",
                    role: "Student3",
                    author: 3),
              ])
        ]),
    Course(
        id: 2,
        name: "Course3",
        teacher: 3,
        approvedBy: 1,
        created: DateTime.now(),
        approved: DateTime.now(),
        threads: [
          Thread(
              id: 1,
              author: 1,
              category: "Kokoti",
              title: "Thread 1 kámo",
              messages: [
                Message(
                    text: "Ahoj hele, vole kde mám káru",
                    role: "Student",
                    author: 2),
                Message(
                    text: "Ahoj hele, vole kde mám káru1",
                    role: "Student1",
                    author: 3),
                Message(
                    text: "Ahoj hele, vole kde mám káru2",
                    role: "Student2",
                    author: 3),
                Message(
                    text: "Ahoj hele, vole kde mám káru3",
                    role: "Student3",
                    author: 2),
              ]),
          Thread(
              id: 1,
              author: 1,
              category: "Kokoti",
              title: "Thread 2 kámo",
              messages: [
                Message(
                    text: "Ahoj hele, vole kde mám káru2",
                    role: "Student",
                    author: 2),
                Message(
                    text: "Ahoj hele, vole kde mám káru21",
                    role: "Student1",
                    author: 3),
                Message(
                    text: "Ahoj hele, vole kde mám káru22",
                    role: "Student2",
                    author: 3),
                Message(
                    text: "Ahoj hele, vole kde mám káru23",
                    role: "Student3",
                    author: 2),
              ])
        ]),
  ];

  List<Course> get courses {
    return [..._courses];
  }

  void addCourse() {
    notifyListeners();
  }

  Course findById(int id) {
    return _courses.firstWhere((element) => element.id == id);
  }
}
