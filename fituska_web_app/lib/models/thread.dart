import 'package:fituska_web_app/models/message.dart';
import 'package:fituska_web_app/models/user.dart';
import 'package:flutter/foundation.dart';

class Thread {
  int id;
  String title;
  bool isClosed;
  int author;
  String category;

  List<Message> messages = [];

  Thread({
      this.id = 0,
      this.title = "",
      this.isClosed = false,
      this.author = 0,
      this.category = "",
      required this.messages
    });
}
