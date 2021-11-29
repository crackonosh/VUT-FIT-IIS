import 'package:fituska_web_app/models/category.dart';
import 'package:fituska_web_app/providers/auth.dart';
import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;

class Categories with ChangeNotifier {
  final api = "164.68.102.86";
  List<Category> _cat = [];

  List<Category> get categories {
    return [..._cat];
  }

  Future<void> addCat(String code, String name, Auth auth) async {
    final Uri url = Uri.parse("http://$api:8000/categories/add");
    try {
      await http
          .post(
        url,
        headers: {
          'Content-type': 'application/json',
          'Authorization': 'Bearer ${auth.token}',
        },
        body: json.encode({"name": name, "course_code": code}),
      )
          .timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      }).then((value) {
        getCats(code);
      });
    } catch (error) {
      print(error);
    }
  }

  Future<void> getCats(String code) async {
    final Uri url = Uri.parse("http://$api:8000/courses/$code/get/categories");
    try {
      await http.get(url).timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      }).then((value) {
        final res = json.decode(value.body);
        _cat.clear();
        res.forEach((elements) {
          _cat.add(Category(id: elements["id"], name: elements["name"]));
          notifyListeners();
        });
      });
    } catch (error) {
      print(error);
    }
  }
}
