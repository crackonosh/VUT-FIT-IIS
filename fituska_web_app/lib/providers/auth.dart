import 'dart:async';
import 'dart:convert';

import 'package:flutter/widgets.dart';
import 'package:http/http.dart' as http;

class Auth with ChangeNotifier {
  String _token = "";
  int _id = -1;
  String _role = "";
  late DateTime _expireryDate = DateTime.now();

  final api = "164.68.102.86";

  bool get isAuth {
    if (_expireryDate.isAfter(DateTime.now()) && _token != "" && _id != -1) {
      return true;
    }
    return false;
  }

  String get token {
    if (_expireryDate.isAfter(DateTime.now()) && _token != "" && _id != -1) {
      return _token;
    }
    return "";
  }

  String get role {
    if (_expireryDate.isAfter(DateTime.now()) && _token != "" && _id != -1) {
      return _role;
    }
    return "";
  }

  int get id {
    if (_expireryDate.isAfter(DateTime.now()) && _token != "" && _id != -1) {
      return _id;
    }
    return -1;
  }

  Future<void> signup(String? nickname, String? password, String? email) async {
    return _authReg(nickname, password, email);
  }

  Future<void> signin(String? email, String? password) async {
    return _authLog(email, password);
  }

  Future<void> _authReg(
      String? nickname, String? password, String? email) async {
    final Uri url = Uri.parse("http://$api:8000/signup");

    Map data = {"name": nickname, "password": password, "email": email};

    var body = json.encode(data);

    try {
      //final response = await http.get(url)
      final response = await http
          .post(url,
              headers: {
                'Content-type': 'application/json',
                //'Access-Control-Allow-Origin': '*',
              },
              body: body)
          .timeout(const Duration(seconds: 4), onTimeout: () {
        throw TimeoutException("Timed out");
      });
      final res = json.decode(response.body);
      if (res.toString() == "{}") {
        throw Error();
      }
      if (res['message'] != "Successfully created new user.") {
        throw Exception(res['message']);
      }
    } catch (error) {
      rethrow;
    }
  }

  Future<void> _authLog(String? email, String? password) async {
    final Uri url = Uri.parse("http://$api:8000/login");
    try {
      final response = await http
          .post(
        url,
        headers: {
          'Content-type': 'application/json',
        },
        body: jsonEncode({
          "email": email,
          "password": password,
        }),
      )
          .timeout(const Duration(seconds: 4), onTimeout: () {
        throw TimeoutException("Timed out");
      });
      final res = json.decode(response.body);
      if (res.toString() == "{}") {
        throw Error();
      }
      _token = res['jwt'];
      _expireryDate = DateTime.fromMillisecondsSinceEpoch(res['exp'] * 1000);
      _id = res['user']["id"];
      _role = res['user']['role'];
    } catch (error) {
      rethrow;
    }
  }
}
