define({
    string: 'test string level 1',
    number: 1,
    array: ['a1 level 1', 'a2 level 1', 'a3 level 1', 'a4 level 1'],
    bool: false,
    func: function() {
        return 'test function level 1';
    },
    object: {
        string: 'test string level 2',
        number: 2,
        array: ['a1 level 2', 'a2 level 2', 'a3 level 2', 'a4 level 2'],
        bool: true,
        func: function() {
            return 'test function level 2';
        },
        object: {
            string: 'test string level 3',
            number: 3,
            array: ['a1 level 3', 'a2 level 3', 'a3 level 3', 'a4 level 3'],
            bool: false,
            func: function() {
                return 'test function level 3';
            },
            object: {
                string: 'test string level 4',
                number: 4,
                array: ['a1 level 4', 'a2 level 4', 'a3 level 4', 'a4 level 4'],
                bool: true,
                func: function() {
                    return 'test function level 4';
                }
            }
        }
    }
});