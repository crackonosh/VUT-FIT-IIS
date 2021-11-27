import 'dart:async';
import 'dart:convert';

import 'package:flutter/widgets.dart';
import 'package:http/http.dart' as http;

class Auth with ChangeNotifier {
  late String _token;
  String username = "";
  late DateTime _exipryDate;

  final api = "164.68.102.86";

  bool get isAuth {
    bool yes = token != null;
    return yes;
  }

  String? get token {
    if (_exipryDate != null &&
        _exipryDate.isAfter(DateTime.now()) &&
        _token != null) {
      return _token;
    }
    return null;
  }

  Future<void> signup(String? nickname, String? password, String? email) async {
    return _authReg(nickname, password, email);
  }

  Future<void> signin(String? nickname, String? password) async {
    return _authLog(nickname, password);
  }

  Future<void> _authReg(
      String? nickname, String? password, String? email) async {
    final Uri url = Uri.parse("http://$api:8000/signup");

    Map data = {"name": nickname, "password": password, "email": email};

    var body = json.encode(data);

    try {
      //final response = await http.get(url)
      final response = await http.post(
        url,
        headers: {
          'Content-type': 'application/json',
          //'Access-Control-Allow-Origin': '*',
        },
        body: body
      ).timeout(const Duration(seconds: 4), onTimeout: () {
        throw TimeoutException("Timed out");
      });
      final res = json.decode(response.body);
      if (res.toString() == "{}") {
        throw Error();
      }
      print(res);
    } catch (error) {
      rethrow;
    }
  }

  Future<void> _authLog(String? nickname, String? password) async {
    final Uri url = Uri.parse("http://$api:8000/login");
    try {
      final response = await http
          .post(
        url,
        headers: {
          'Content-type': 'application/json',
        },
        body: jsonEncode({
          "name": nickname,
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
      print(res);
    } catch (error) {
      rethrow;
    }
  }
}
