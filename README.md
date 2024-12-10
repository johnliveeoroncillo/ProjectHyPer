
# ProjectHyPer

ProjectHyPer is a high-performance, full-stack PHP framework designed to accelerate web application development. Inspired by modern frameworks like Nuxt.js and Prisma, ProjectHyPer offers a streamlined developer experience, robust features, and a focus on performance and scalability.

## Features

- Flexible Routing System: Define clean and maintainable routes for your application
- Easily create RESTful APIs
- Page Navigation Guards
- Layouts
- Dynamic footers and headers
- Prisma like plugin
- Auto database table and model detections
- Migrations
- Tailwind
- Custom Modal
- Typeorm like plugin


## Author

- [@johnliveeoroncillo](https://github.com/johnliveeoroncillo)


## Environment Variables

To run this project, you will need to add the following environment variables to your .env file

```
APP_NAME=

#### DATABASE
DB_HOST=
DB_USERNAME=
DB_PASSWORD=
DB_NAME=

#### MAILER
SMTP_HOST=
SMTP_PORT=
SMTP_USER=
SMTP_PASS=


#### REDIRECT URLS
HOME_FILE=
LOGIN_URL=
PROTECTED_URL=
```


## Documentation

#### Database
#### Example we have 3 tables
- news (table)
   - id (field)
   - name (field)
- news_details (table)
   - id (field)
   - news_id (field)
   - content (field)
- news_attachments (table)
   - id (field)
   - news_details_id (field)
   - url (field)
---

#### `find`
`$db->{$table}->find()`
#### Usage:
```php
    $db->news->find();
    $db->news_details->find();
    $db->news_attachments->find();
    // returns list of news
```

#### `findOne`
`$db->{$table}->findOne()`
```php
    $db->news->findOne();
    $db->news_details->findOne();
    $db->news_attachments->findOne();
    // returns 1 news
```

#### Working with conditions
```
    // Retrieve using primary ID
    $id = 1;
    $news = $db->news->findOne($id);
    $news = $db->news->find($id);

    // Other column conditions
    $news = $db->news_details->findOne(
        array(
            'news_id' => $id
        )
    );
```

#### Other complex conditions
```
    // Equal
    // id = 1
    $where = array(
        'id' => array(
            'eq' => 1
        )
    );
    or
    $where = array('id' => 1);

    // In
    // id in (1,2,3)
    $where = array(
        'id' => array(
            'in' => array(1, 2, 3)
        )
    );

    // Greater than
    // id > 1
    $where = array(
        'id' => array(
            'gt' => 1
        )
    );

    // Geater than or Equal
    // id >= 1
    $where = array(
        'id' => array(
            'gte' => 1
        )
    );

    // Less than
    // id < 1
    $where = array(
        'id' => array(
            'lt' => 1
        )
    );

    // Less than or Equal
    // id <= 1
    $where = array(
        'id' => array(
            'lte' => 1
        )
    )

    // Like
    // name like '%test%'
    $where = array(
        'name' => array(
            'like' => 'test'
        )
    );

    // Between
    // created_at between 'date1' and 'date2'
    $where = array(
        'created_at' => array(
            'between' => array('date1', 'date2')
        )
    );

    $news = $db->news->find($where);
```

#### How about the or's and the and's ?
```
    // name like '%your test%' or name = 'my test'
    $or_condition = array(
        'name' => array(
            'or' => array(
                'like' => 'your test',
                'eq' => 'my test',
            )
        )
    );
    
    // id >= 1 and id <= 20
    $and_condition = array(
        'id' => array(
            'and' => array(
                'gte' => 1,
                'lte' => 20
            )
        )
    );
```

#### Combining conditions
```
$where = array(
    'name' => array(
        'like' => 'your test',
        'eq' => 'my test'
    ),
    'created_at' => array(
        'between' => array(
            'date1',
            'date2'
        )
    )
)

// That condition is equal to
name like '%your test%' and name = 'my test')
    and created_at between 'date1' and 'date2' 
```

#### Are you looking for the other stuffs like order by ? group by ? having ?. Using those additional parameters we need to adjust our `where` conditions
```
$condition = array('id' => 1);
// to
$condition = array(
    'where' => array(
        'id' => 1
    )
)
// we can use also the other conditions
$condition = array(
    'where' => array(
        'id' => array(
            'in' => array(1,2,3)
        )
    )
)

// Using Order by
$condition = array(
    'where' => array(
        'id' => array(
            'in' => array(1,2,3)
        )
    ),
    'order' => array(
        'id' => 'DESC'
    )
)
// or multiple order by
$condition = array(
    'where' => array(
        'id' => array(
            'in' => array(1,2,3)
        )
    ),
    'order' => array(
        'id' => 'DESC',
        'created_at' => 'ASC'
    )
)

// Using Limit
$condition = array(
    'where' => array(
        'id' => array(
            'in' => array(1,2,3)
        )
    ),
    'limit' => 2,
    'order' => array(
        'id' => 'DESC'
    )
)

// Using Offset
$condition = array(
    'where' => array(
        'id' => array(
            'in' => array(1,2,3)
        )
    ),
    'limit' => 2,
    'offset' => 0,
    'order' => array(
        'id' => 'DESC'
    )
)

// Using Group by
$condition = array(
    'where' => array(
        'id' => array(
            'in' => array(1,2,3)
        )
    ),
    'limit' => 2,
    'offset' => 0,
    'order' => array(
        'id' => 'DESC'
    ),
    'group' => array(
        'id',
        'name'
    )
)

// Using Having
$condition = array(
    'where' => array(
        'id' => array(
            'in' => array(1,2,3)
        )
    ),
    'limit' => 2,
    'offset' => 0,
    'order' => array(
        'id' => 'DESC'
    ),
    'group' => array(
        'id',
        'name'
    ),
    'having' => array(
        'id > 1',
        'id <= 2'
    )
)
```

#### Condition References

| Parameter | Operator     | Description                |
| :-------- | :------- | :------------------------- |
| `eq` | `=` | Equal |
| `in` | `in (...)` | In |
| `gt` | `>` | Greater than |
| `gte` | `>=` | Greater than or equal |
| `lt` | `<` | Less than |
| `lte` | `=` | Less than or equal |
| `like` | `like "%..%"` | Like |
| `between` | `between '..' and '..'` | Between |
| `or` | `($ = '' or $1 = '')` | Or |
| `and` | `($ = '' and $1 = '')` | And |
| `limit` | `LIMIT` | Limit |
| `group` | `GROUP BY ...` | Group By |
| `having` | `HAVING ...` | Having |
| `offset` | `OFFSET` | Offset |

#### Since we are inspired by Prisma we (I) also adopts the auto relationship.
##### Like for example I want to retrieve the `news` as well as the `news_details` under that news, we can do that easily.

```
    // Multiple Return
    $news_w_details = $db->news->findOne(
        array(
            'id' => 1,
            'include' => array(
                'news_details' => true
            )
        )
    )
    // Returns
    {
        id: 1,
        name: 'News1',
        news_details: [...]
    }

    // Single Return
    $news_w_detail = $db->news->findOne(
        array(
            'id' => 1,
            'include' => array(
                'news_details' => array(
                    'single' => true // ADD THIS PARAMETER
                )
            )
        )
    )
    // Returns
    {
        id: 1,
        name: 'News1',
        news_details: {
            id: 1,
            news_id: 1,
            content: '',
        }
    }

    // You can add include inside the relation as well
    // if there's another table linked to each other
    // Like for example I want to retrieve the news_attachments as well
    // which is connected to news_details
    $news_complete = $db->news->findOne(
        array(
            'id' => 1,
            'include' => array(
                'news_details' => array(
                    'single' => true
                    'include' => array(
                        'news_attachments' => true
                    )
                )
            )
        )
    )
    // Returns
    {
        id: 1,
        name: 'News1',
        news_details: {
            id: 1,
            news_id: 1,
            content: '',
            news_attachments: [...],
        }
    }

    // You can also combined it with where conditions
    $news_complete = $db->news->findOne(
        array(
            'id' => 1,
            'include' => array(
                'news_details' => array(
                    'where' => array(
                        'between' => array(
                            'date1',
                            'date2'
                        )
                    )
                    'single' => true
                    'include' => array(
                        'news_attachments' => true
                    )
                )
            )
        )
    )
    // This will retrieve news_details connected to news
    // and created_at between 'date1' and 'date2'
```

#### `insert`
`$db->{$table}->insert()`
#### Usage:
```php
    // First we declare our model
    $news = new News();
    $news->name = 'Test'

    $data = $db->news->insert($news);
    // Returns
    {
        id: 1,
        name: 'Test',
        created_at: '..',
        updated_at: null,
        deleted_at: null
    }
```

#### `update`
`$db->{$table}->update()`
#### Usage:
```php
    $where = array('id' => 1);
    $payload = array('name' => 'Update name');
    $data = $db->news->update($where, $payload);
    // Returns
    {
        id: 1,
        name: 'Update name',
        created_at: '..',
        updated_at: '..',
        deleted_at: null
    }
```

#### `delete`
`$db->{$table}->delete()`
This will hard delete your record and irreversible
#### Usage:
```php
    $where = array('id' => 1);
    $db->news->delete($where);
    or
    $db->news->delete(1); // where 1 is ID
```


#### `soft delete`
`$db->{$table}->softDelete()`
This will soft delete your record and reversible
#### Usage:
```php
    $where = array('id' => 1);
    $db->news->softDelete($where);
    or
    $db->news->softDelete(1); // where 1 is ID
```

#### `upsert`
`$db->{$table}->save()`
#### Usage:
```php
    // First we try to search the data
    $news = $db->news->findOne(1);
    if (empty($news)) {
        // If not existing instantiate new model
        $news = new News();
    }
    $news->name = 'My news';

    $data = $db->news->save($news);
    // If existing
    {
        id: 1,
        name: 'My News',
        created_at: '..',
        updated_at: '..',
        deleted_at: null
    }
    // If not
    {
        id: 2,
        name: 'My News',
        created_at: '..',
        updated_at: null,
        deleted_at: null
    }
```
## Roadmap
- [ ]  count
- [ ]  offset
- [ ]  addWhere
- [ ]  addGroup
- [ ]  pagination
- [ ]  lastQuery
- [ ]  lastInsertedId
- [ ]  error
- [ ]  API Documentation
- [ ]  Route Documentation
- [ ]  Layout/Header/Footer Documentation
- [ ]  Migration Documentation
- [ ]  Modal Documentation
- [ ]  Route Guard Documentation
- [ ]  Vue component loop and conditions (FOR FUTURE)
- [ ]  Optimizations

