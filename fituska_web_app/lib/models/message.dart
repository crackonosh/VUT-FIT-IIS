import 'package:fituska_web_app/models/user.dart';

class Message {
  String text;
  String role;
  int author;

  Message({
    this.text = "", this.role = "", this.author = 0,
  });
}
