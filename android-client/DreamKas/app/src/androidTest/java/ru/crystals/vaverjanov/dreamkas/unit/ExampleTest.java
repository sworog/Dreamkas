package ru.crystals.vaverjanov.dreamkas.unit;

import android.test.InstrumentationTestCase;

public class ExampleTest extends InstrumentationTestCase
{
    public void testFalse() throws Exception
    {
        final int expected = 1;
        final int reality = 1;
        assertEquals(expected, reality);
    }
}
