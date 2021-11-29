import 'package:fituska_web_app/models/category.dart';
import 'package:fituska_web_app/models/thread.dart';
import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/models/course.dart';

class CoursProv with ChangeNotifier {
  final api = "164.68.102.86";
  List<Course> _cour = [];

  List<Course> get courses {
    return [..._cour];
  }

  Future<void> getCourses() async {
    final Uri url = Uri.parse("http://$api:8000/courses/get/approved");
    try {
      await http.get(url).timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      }).then((value) {
        final res = json.decode(value.body);
        _cour.clear();
        res.forEach((element) {
          List<Thread> threads = [];
          _cour.add(Course(
            id: element["code"],
            name: element["name"], 
            teacher: element["lecturer"]["id"],
            threads: threads,
            created: DateTime.parse(element["created_on"]) ,
            approved: DateTime.parse(element["approved_on"]) ,
          ));
        });
        notifyListeners();
      });
    } catch (error) {
      print(error);
    }
  }
}
