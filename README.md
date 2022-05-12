# BZFlag Social

BZFlag Social is a simple forum and messaging system. It is still in the early stages of development.

## Requirements

* PHP 7.4 (may work on as low as 7.2)
* Postgresql (tested on version 13)

## Roadmap

* [X] List (Sub)Categories
* [X] List topics in a category
  * [ ] Category pagination 
* [X] Display posts in a topic
  * [ ] Topic pagination
  * [ ] File attachments
  * [ ] Emoji (?)
  * [ ] Copy most of the phpBB3 s9e/text-formatter bundle
* [ ] Subscribe to topic updates
* [ ] Private messaging
* [ ] Report post/message
* [ ] Friends list
* [ ] Email notifications
* [ ] OpenID Connect login flow (?)
  * [ ] Group membership checks for forum and category permissions (admin users, moderators, private categories, etc.) (?)
* [ ] User preferences
  * [ ] Locale
  * [ ] Theme
  * [ ] Timezone
* [ ] User profile
  * [ ] Contact information
  * [ ] Website
  * [ ] Edit signature
* [ ] Ability to user to delete their own account (Or an API so the custom OIDC IdP can trigger a deletion?)
* [ ] Moderation tools
  * [ ] Lock topic
  * [ ] Delete topic
  * [ ] Delete post
  * [ ] Delete multiple posts
  * [ ] Edit post
  * [ ] Split topic
* [ ] Administration tools
  * [ ] Manage categories
    * [ ] Create/edit/delete categories
    * [ ] Adjust the category order
  * [ ] Manage users
    * [ ] Delete user (How would this work with OIDC?) (and posts/messages)
    * [ ] Deactivate user
    * [ ] Ban user, hostname, IP, email (?)
* [ ] API
  * [ ] Private
    * [ ] Create user
    * [ ] View user
    * [ ] Delete user (?)
    * [ ] Create topic
  * [ ] Public
    * [ ] View private messages
    * [ ] Create/send private message
    * [ ] Search users (?)
    * [ ] List friends
    * [ ] Fetch public user profile (uuid, name, avatar)
* [ ] Tool to convert phpBB3 to BZFlag Social

## License

Copyright (C) 2022 Snail Paste, LLC

This program is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public
License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
details.

You should have received a copy of the GNU Affero General Public License along with this program.  If not, see
<https://www.gnu.org/licenses/>.

# Third-party licenses

public/images/logo.svg is Apache 2.0 licensed from [Carbon Design System](https://github.com/carbon-design-system/carbon/blob/main/packages/icons/src/svg/32/forum.svg)