import 'dart:convert';

import 'package:fituska_web_app/models/user.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class Users with ChangeNotifier {
  final api = "164.68.102.86";

  List<User> _users = [];

  List<User> get users {
    return [..._users];
  }

  void addUser(
    int id,
    String name,
    String email,
    String adress,
    String phone,
    int score,
  ) {
    _users.add(User(
        id: id,
        name: name,
        email: email,
        adress: adress,
        phone: phone,
        score: score));
  }

  User findById(int id) {
    return _users.firstWhere((element) => element.id == id);
  }

  Future<void> initUsers() async {
    final Uri url = Uri.parse("http://$api:8000/users");
    _users.clear();
    try {
      final response = await http.get(url).timeout(const Duration(seconds: 4),
          onTimeout: () {
        throw Exception("You Timed out");
      }).then((value) {
        final res = json.decode(value.body);
        res.forEach((element) {
          addUser(
              element['id'],
              element['name'],
              element['email'],
              element['address'].toString(),
              element['phone'].toString(),
              element['score']);
        });
        notifyListeners();
      });
    } catch (error) {
      print(error);
    }
  }
}
