define(function(require) {
    return {
        "id": "534d4ab8e8bf4d43158b4567",
        "name": "Пиво Балтика №7",
        "units": "kg",
        "vat": 10,
        "purchasePrice": 33,
        "sku": "10007",
        "retailPriceMin": 43.89,
        "retailPriceMax": 47.52,
        "retailMarkupMin": 33,
        "retailMarkupMax": 44,
        "retailPricePreference": "retailMarkup",
        "type": "weight",
        "typeProperties": {
            nameOnScales: 'Помидор (Испания)',
            descriptionOnScales: 'Хороший поидор (Испания)',
            ingredients: 'Помидор, пестициды в ассортименте',
            nutritionFacts: 'Она огромна',
            shelfLife: 48
        },
        "rounding": {
            "name": "nearest1",
            "title": "до копеек"
        },
        "subCategory": {
            "id": "534d49dae8bf4d254a8b456f",
            "name": "Светлое",
            "rounding": {
                "name": "nearest1",
                "title": "до копеек"
            },
            "category": {
                "id": "534d49d6e8bf4dc1498b4571",
                "name": "Пиво",
                "rounding": {
                    "name": "nearest1",
                    "title": "до копеек"
                },
                "group": {
                    "id": "534d49d1e8bf4d41158b456a",
                    "name": "Алкоголь",
                    "rounding": {
                        "name": "nearest1",
                        "title": "до копеек"
                    },
                    "categories": []
                },
                "subCategories": []
            }
        }
    }
});