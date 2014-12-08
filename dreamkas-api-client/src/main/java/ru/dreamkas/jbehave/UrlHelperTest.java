package ru.dreamkas.jbehave;

import org.junit.After;
import org.junit.Before;
import org.junit.Test;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apihelper.UrlHelper;

import static org.junit.Assert.assertThat;
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

    @After
    public void after() {
        ApiStorage.getConfigurationVariableStorage().setProperty("webdriver.base.url", null);
    }
}
