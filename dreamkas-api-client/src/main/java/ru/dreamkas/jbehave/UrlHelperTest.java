package ru.dreamkas.jbehave;

import org.junit.After;
import org.junit.Before;
import org.junit.Test;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apihelper.UrlHelper;

import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Modifier;

import static org.junit.Assert.*;
import static org.hamcrest.Matchers.is;

public class UrlHelperTest {

    @Before
    public void before() {
        ApiStorage.getConfigurationVariableStorage().setProperty("webdriver.base.url", "test.autotests.webfront.lighthouse.pro");
    }

    @Test
    public void testGetWebFrontUrlMethod() {
        assertThat(
                UrlHelper.getWebFrontUrl(),
                is("test.autotests.webfront.lighthouse.pro"));
    }

    @Test
    public void testGetApiUrl() {
        assertThat(
                UrlHelper.getApiUrl(),
                is("test.autotests.api.lighthouse.pro"));
    }

    @Test
    public void testGetApiUrlWithParams() {
        assertThat(
                UrlHelper.getApiUrl("/test"),
                is("test.autotests.api.lighthouse.pro/api/1/test"));
    }

    @Test
    public void testUrlHelperConstructorIsPrivate() throws NoSuchMethodException, IllegalAccessException, InvocationTargetException, InstantiationException {
        assertTrue(Modifier.isPrivate(UrlHelper.class.getDeclaredConstructor().getModifiers()));
    }

    @Test(expected = InvocationTargetException.class)
    public void testUrlHelperConstructorThrowsException() throws NoSuchMethodException, IllegalAccessException, InvocationTargetException, InstantiationException {
        Constructor<UrlHelper> c = UrlHelper.class.getDeclaredConstructor();
        c.setAccessible(true);
        c.newInstance();
    }

    @After
    public void after() {
        ApiStorage.getConfigurationVariableStorage().setProperty("webdriver.base.url", null);
    }
}
