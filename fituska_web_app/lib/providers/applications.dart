import 'dart:async';
import 'dart:convert';

import 'package:fituska_web_app/models/student_application.dart';
import 'package:fituska_web_app/providers/auth.dart';
import 'package:flutter/widgets.dart';
import 'package:http/http.dart' as http;

class Applications with ChangeNotifier {
  final api = "164.68.102.86";
  List<StudentApplication> _sap = [];

  List<StudentApplication> get applications {
    return [..._sap];
  }

  Future<void> setAccept(int id, String code, Auth auth) async {
    final Uri url = Uri.parse("http://$api:8000/applications/${id}/approve");
    try {
      final response = await http.put(url, headers: {
        'Authorization': 'Bearer ${auth.token}',
      }).timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      }).then((value) {
        getAppliSilent(code, auth).then((value) => notifyListeners());
      });
    } catch (error) {
      print(error);
    }
  }

  Future<void> setDecline(int id, String code, Auth auth) async {
    final Uri url = Uri.parse("http://$api:8000/applications/${id}/revoke");
    try {
      final response = await http.put(url, headers: {
        'Authorization': 'Bearer ${auth.token}',
      }).timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      }).then((value) {
        getAppliSilent(code, auth).then((value) => notifyListeners());
      });
    } catch (error) {
      print(error);
    }
  }

  Future<void> sendAppli(String code, Auth auth) async {
    final Uri url =
        Uri.parse("http://$api:8000/courses/${code}/application/add");
    try {
      await http.post(url,
      headers: {
        'Content-type': 'application/json',
        'Authorization': 'Bearer ${auth.token}',
      },
      ).timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      }).then((value) {
          getAppliSilent(code, auth).then((value) => notifyListeners());
      });
    } catch (error) {
      print(error);
    }
  }

  Future<void> getAppli(String code, Auth auth) async {
    final Uri url =
        Uri.parse("http://$api:8000/courses/$code/applications/get");
    try {
      await http.get(url, headers: {
        'Authorization': 'Bearer ${auth.token}',
      }).timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      }).then((value) {
        final res = json.decode(value.body);
        _sap.clear();
        res.forEach((entity) {
          int id = entity["id"];
          String s = entity["student"]["name"];
          bool app = entity["status"];
          _sap.add(StudentApplication(id: id, student: s, approved: app));
          notifyListeners();
        });
      });
    } catch (error) {
      print(error);
    }
  }

  Future<void> getAppliSilent(String code, Auth auth) async {
    final Uri url =
        Uri.parse("http://$api:8000/courses/$code/applications/get");
    try {
      final response = await http.get(url, headers: {
        'Authorization': 'Bearer ${auth.token}',
      }).timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      });
      final res = json.decode(response.body);
      _sap.clear();
      res.forEach((entity) {
        int id = entity["id"];
        String s = entity["student"]["name"];
        bool app = entity["status"];
        _sap.add(StudentApplication(id: id, student: s, approved: app));
      });
    } catch (error) {
      print(error);
    }
  }
}
