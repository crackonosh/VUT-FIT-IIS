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
      print(error);
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
      print(error);
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
      print(error);
      return Future.delayed(Duration.zero);
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
      print(error);
      return Future.delayed(Duration.zero);
    }
  }
}
