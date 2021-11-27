import 'dart:async';
import 'dart:convert';

import 'package:flutter/widgets.dart';
import 'package:http/http.dart' as http;

class Auth with ChangeNotifier {
  // String _token;
  // String username = "";
  // DateTime _exipryDate;

  final api = "localhost";

  // bool get isAuth {
  //   bool yes = token != null;
  //   return yes;
  // }

  // String? get token {
  //   if (_exipryDate != null &&
  //       _exipryDate.isAfter(DateTime.now()) &&
  //       _token != null) {
  //     return _token;
  //   }
  //   return null;
  // }

  Future<void> signup(String? nickname, String? password, String? email) async {
    return _auth(nickname, password, email, "singup");
  }

  Future<void> _auth(
      String? nickname, String? password, String? email, String p) async {
    final Uri url = "http://$api:8000/$p/" as Uri;
    try {
      final response = await http
          .post(
        url,
        headers: {
          'Content-type': 'application/json',
          'Accept': 'application/json',
        },
        body: json.encode({
          "name": nickname,
          "password": password,
          "email": email,
          "role": 1,
        }),
      )
          .timeout(Duration(seconds: 4), onTimeout: () {
        throw TimeoutException("Timed out");
      });
      final res = json.decode(response.body);
      if (res.toString() == "{}") {
        throw Error();
      }
      print(res);

      if (res['status'] == 'fail') ;
    } catch (error) {
      throw error;
    }
  }
}
