import 'dart:convert';
import 'package:fituska_web_app/models/message.dart';
import 'package:fituska_web_app/models/thread.dart';
import 'package:fituska_web_app/providers/auth.dart';

import 'package:flutter/material.dart';
import 'package:collection/collection.dart';
import 'package:http/http.dart' as http;

import '../models/course.dart';

class Courses with ChangeNotifier {
  final api = "164.68.102.86";

  List<Course> _courses = [];
  /*List<Course> _courses = [
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
  ];*/

  List<Course> get courses {
    return [..._courses];
  }

  void addCourse(
      String id, String name, int lec, String app, String ctr, List<Thread> t) {
    _courses.add(Course(
        id: id,
        name: name,
        teacher: lec,
        threads: t,
        created: DateTime.parse(ctr),
        approved: DateTime.parse(app)));
  }

  bool isIn(String id) {
    Course? c = _courses.firstWhereOrNull((element) => element.id == id);
    if (c == null) {
      return false;
    }
    return true;
  }

  Course findById(String id) {
    return _courses.firstWhere((element) => element.id == id);
  }

  Future<void> setClosed(String cour, int id, Auth auth) async {
    final Uri url = Uri.parse("http://$api:8000/threads/${id}/close");
    try {
      final response = await http.put(url, headers: {
        'Authorization': 'Bearer ${auth.token}',
      }).timeout(Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      }).then((value) {
        List<Thread> thre = findById(cour).threads;
        for (Thread thr in thre) {
          if (thr.id == id) {
            thr.isClosed = true;
            break;
          }
        }
        notifyListeners();
      });
    } catch (error) {
      rethrow;
    }
  }

  Future<void> initCourses() async {
    _courses.clear();
    final Uri url = Uri.parse("http://$api:8000/courses/get/approved");
    try {
      final response = await http.get(url, headers: {
        'Accept': 'application/json',
      }).timeout(const Duration(seconds: 10), onTimeout: () {
        throw Exception("You Timed out");
      });
      final res = json.decode(response.body);
      res.forEach((element) async {
        List<Thread> threads = await getThread(element["code"]);
        addCourse(element["code"], element["name"], element["lecturer"]["id"],
            element["approved_on"], element["created_on"], threads);
        notifyListeners();
      });
    } catch (error) {
      rethrow;
    }
  }

  Future<List<Thread>> getThread(String code) async {
    final Uri url = Uri.parse("http://$api:8000/courses/${code}/threads/get");
    try {
      final response = await http.get(url).timeout(const Duration(seconds: 10),
          onTimeout: () {
        throw Exception("You Timed out");
      });
      final res = json.decode(response.body);
      List<Thread> threads = [];
      res.forEach((elemen) async {
        List<Message> messages = await initMessages(elemen["id"]);
        threads.add(Thread(
            id: elemen["id"],
            title: elemen["title"],
            isClosed: elemen["is_closed"],
            author: elemen["author"]["id"],
            category: elemen["category"],
            messages: messages));
      });
      return threads;
    } catch (error) {
      rethrow;
    }
  }

  Future<List<Message>> initMessages(int id) async {
    final Uri url = Uri.parse("http://$api:8000/threads/id/$id/get");
    try {
      final response = await http.get(url).timeout(const Duration(seconds: 10),
          onTimeout: () {
        throw Exception("You Timed out");
      });
      List<Message> msg = [];
      final res = json.decode(response.body);
      res["messages"].forEach((element) {
        msg.add(Message(
            text: element["text"],
            role: element["role"],
            author: element["author"]["id"]));
      });
      return msg;
    } catch (error) {
      rethrow;
    }
  }
}
