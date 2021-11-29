import 'dart:async';
import 'dart:convert';

import 'package:fituska_web_app/providers/auth.dart';
import 'package:flutter/widgets.dart';
import 'package:http/http.dart' as http;

class ChangeRole with ChangeNotifier {
  final api = "164.68.102.86";

  Future<void> changeId(int id, int role, Auth auth) async {
    final Uri url = Uri.parse("http://$api:8000/users/${id}/role/${role}");
    try {
      final response = await http.put(url, headers: {
        'Authorization': 'Bearer ${auth.token}',
      }).timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      });
    } catch (error) {
      rethrow;
    }
  }
}