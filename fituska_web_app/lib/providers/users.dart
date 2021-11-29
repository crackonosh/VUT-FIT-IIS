import 'package:fituska_web_app/models/user.dart';
import 'package:flutter/material.dart';

class Users with ChangeNotifier {
  List<User> _users = [
    User(id: 1, name: "Kuřecí stehýnko", email: "kuře@steh.no"),
    User(id: 2, name: "Kerooon", email: "ke@ro.n"),
    User(id: 3, name: "Sotíík", email: "soti@soti.soti"),
  ];

  List<User> get users {
    return [..._users];
  }

  void addUser() {
    notifyListeners();
  }

  User findById(int id) {
    return _users.firstWhere((element) => element.id == id);
  }
}
