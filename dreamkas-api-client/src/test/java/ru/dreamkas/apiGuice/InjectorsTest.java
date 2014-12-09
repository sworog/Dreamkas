package ru.dreamkas.apiGuice;

import org.junit.Test;

import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Modifier;

import static org.junit.Assert.assertTrue;

public class InjectorsTest {

    @Test
    public void testInjectorsConstructorIsPrivate() throws NoSuchMethodException, IllegalAccessException, InvocationTargetException, InstantiationException {
        assertTrue(Modifier.isPrivate(Injectors.class.getDeclaredConstructor().getModifiers()));
    }

    @Test(expected = InvocationTargetException.class)
    public void testInjectorsConstructorThrowsException() throws NoSuchMethodException, IllegalAccessException, InvocationTargetException, InstantiationException {
        Constructor<Injectors> c = Injectors.class.getDeclaredConstructor();
        c.setAccessible(true);
        c.newInstance();
    }
}
