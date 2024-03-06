<h1 align="center">HanStudio_EventPageBuilder</h1> 

## Table of contents

- [Installation](#installation)
- [Summary](#summary)
- [Usage](#usage)
- [How It Works]($howItWorks)

## Summary

Creating a web page for a company event can be exhausting exprience, revisions after revisions. This module enables
users to create a responsive company event web pages based on information users provide in Magento 2 admin grid.

The frontend is developed with React 18 with TypeScript so that it is highly scalable and customizable.

## Usage

Working...

## How It Works

1. Upon installation of this module, the module creates 4 new tables in the Magento 2 database. The tables are:

- `hanstudio-eventpagebuilder-event` - This table stores the event information such as event name, event date, event
  timezone location, and event description.
- `hanstudio-eventpagebuilder-schedule` - This table stores the event schedule information such as schedule date,
  schedule
  time, schedule title, and schedule description.
- `hanstudio-eventpagebuilder-speaker` - This table stores the event speaker information such as speaker name, speaker
  title, speaker description, and speaker image.
- `hanstudio-eventpagebuilder-schspk` - This table stores relationships between schedule and speaker.

![Database Diagram](https://github.com/jhoonhan/magento2-event-page-builder/blob/master/docs/database-structure.png?raw=true)

2. The module creates a new admin grid in the Magento 2 admin panel under the Content > Event Page Builder. The admin
   grid is used to manage the event, schedule, and speaker information. The master admin can authorize access to other
   users
   to the module.

3. The module creates a CRUD API access points for the frontend to access the event.

```
Example http request to the API:

https://testsite.dev/rest/V1/data/1
// This will return the event information with the id of 1 with associated schedules and speakers.
```

4. When a user create new event, the module automatically creates a CMS Block with given url key. The generated CMS
   Block can be inserted to any page in the Magento 2 store to display the event information.

5. Currently, in version 1, it only creates a CMS Block for an event programme. In the future version, it will create a
   CMS Page for other information such as event speakers, event location, and event sponsors.

