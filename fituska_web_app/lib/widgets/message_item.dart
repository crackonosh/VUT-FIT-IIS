import 'package:fituska_web_app/providers/users.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class MessageItem extends StatelessWidget {
  final String text;
  final String role;
  final int author;

  MessageItem(this.text, this.role, this.author);

  @override
  Widget build(BuildContext context) {
    var auth = Provider.of<Users>(context).findById(author);
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 15, vertical: 4),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: <Widget>[
          ListTile(
            leading: Icon(Icons.messenger_outline_sharp),
            title: Text(auth.name),
            subtitle: Text(text),
          ),
          Row(
            mainAxisAlignment: MainAxisAlignment.end,
            children: <Widget>[
              IconButton(onPressed: () => {}, icon: Icon(Icons.thumb_up)),
            ],
          )
        ],
      ),
    );
  }
}
