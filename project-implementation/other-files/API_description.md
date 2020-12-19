# API Description

## GET /api/polygons_coordinates.php

#### REQUEST

``` 
```

#### RESPONSE

``` json
{
    "1": [
        [
            [
                37,
                -109.05
            ],
            [
                41,
                -102.03
            ],
            [
                41,
                -102.05
            ]
        ]
    ]
}
```

## GET /api/polygons_availabilities.php

#### REQUEST

``` 
hours = 23
minutes = 12
```

#### RESPONSE

``` json
{
    "1": {
        "cent": [
            12.34,
            43.21
        ],
        "perc": 0.123
    }
}
```

## GET /admin/api/polygon_parking_info.php

#### REQUEST

``` json
id = 1
```

#### RESPONSE

``` json
{
    "success": true,
    "id": 1,
    "available_spots": 14,
    "curve": "2",
    "curves": {
        "1": "Κέντρο Πόλης",
        "2": "Περιοχή Κατοικίας",
        "3": "Περιοχή σταθερής ζήτησης"
    }
}
```

## POST /admin/api/polygon_parking_info.php

#### REQUEST

``` json
{
    "id": 1,
    "available_spots": 10,
    "curve": "3",
}
```

#### RESPONSE

``` json
{
    "success": true,
    "id": 1,
    "available_spots": 10,
    "curve": "3",
    "curves": {
        "1": "Κέντρο Πόλης",
        "2": "Περιοχή Κατοικίας",
        "3": "Περιοχή σταθερής ζήτησης"
    }
}
```

## GET /api/parking_spot.php

#### REQUEST

``` json
{
    "unixtime": 123123123123,
    "max_distance": 100,
    "x": 40.12383205092458023,
    "y": 22.08924809234283948,
}
```

#### RESPONSE
``` json
{
    "success": "true",
    "spots": [
        {
            "x": 40.643012616714856,
            "y": 22.93400457702626,
            "distance": 0.32
        },
        {
            "x": 40.64,
            "y": 22.93,
            "distance": 0.25
        }
    ]
}
```