import 'dart:async';
import 'dart:convert';
import 'dart:async';
import 'dart:html';

import 'package:fituska_web_app/models/course_application.dart';
import 'package:fituska_web_app/models/student_application.dart';
import 'package:fituska_web_app/providers/auth.dart';
import 'package:flutter/widgets.dart';
import 'package:http/http.dart' as http;

class Course_Applications with ChangeNotifier {
  final api = "164.68.102.86";
  List<CourseApplication> _sap = [];

  List<CourseApplication> get applications {
    return [..._sap];
  }

  Future<void> setAccept(String code, Auth auth) async {
    final Uri url = Uri.parse("http://$api:8000/courses/${code}/approve");
    try {
      final response = await http.put(url, headers: {
        'Authorization': 'Bearer ${auth.token}',
      }).timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      }).then((value) {
        getAppliSilent(auth).then((value) => notifyListeners());
      });
    } catch (error) {
      rethrow;
    }
  }

  Future<void> setDecline(String code, Auth auth) async {
    final Uri url = Uri.parse("http://$api:8000/courses/${code}/remove");
    try {
      final response = await http.put(url, headers: {
        'Authorization': 'Bearer ${auth.token}',
      }).timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      }).then((value) {
        getAppliSilent(auth).then((value) => notifyListeners());
      });
    } catch (error) {
      rethrow;
    }
  }

  Future<void> getAppli(Auth auth) async {
    final Uri url = Uri.parse("http://$api:8000/courses/get/not-approved");
    try {
      await http.get(url, headers: {
        'Authorization': 'Bearer ${auth.token}',
      }).timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      }).then((value) {
        final res = json.decode(value.body);
        _sap.clear();
        res.forEach((entity) {
          String code = entity["code"];
          String s = entity["name"];
          _sap.add(CourseApplication(code: code, course: s));
          notifyListeners();
        });
      });
    } catch (error) {
      rethrow;
    }
  }

  Future<void> getAppliSilent(Auth auth) async {
    final Uri url = Uri.parse("http://$api:8000/courses/get/not-approved");
    try {
      final response = await http.get(url, headers: {
        'Authorization': 'Bearer ${auth.token}',
      }).timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      });
      final res = json.decode(response.body);
      _sap.clear();
      res.forEach((entity) {
        String code = entity["code"];
        String s = entity["name"];
        _sap.add(CourseApplication(code: code, course: s));
      });
    } catch (error) {
      rethrow;
    }
  }
}
