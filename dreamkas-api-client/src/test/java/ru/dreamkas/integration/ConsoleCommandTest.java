package ru.dreamkas.integration;

import org.json.JSONException;
import org.junit.After;
import org.junit.Before;
import org.junit.Ignore;
import org.junit.Test;
import ru.dreamkas.api.http.HttpExecutor;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apihelper.UrlHelper;
import ru.dreamkas.console.backend.BackendCommand;
import ru.dreamkas.jbehave.GivenConsoleCommandsUserSteps;

import java.io.IOException;

@Ignore
public class ConsoleCommandTest {

    @Before
    public void before() throws IOException, InterruptedException {
        ApiStorage.getConfigurationVariableStorage().setProperty("webdriver.base.url", "integration-tests.autotests.webfront.lighthouse.pro");
        System.setProperty("api.staging", "autotests");
        new BackendCommand("deploy").run();
    }

    @Test
    @Ignore
    public void testCreateUser() throws InterruptedException, JSONException, IOException {
        new GivenConsoleCommandsUserSteps().givenTheUserRunsTheSymfonyUserCreateCommandWithParams("integration@lighthouse.pro", "lighthouse");
        String responce = HttpExecutor.getHttpRequestable("integration@lighthouse.pro", "lighthouse").executeGetRequest(UrlHelper.getApiUrl("/current"));
    }

    @After
    public void after() throws IOException, InterruptedException {
        new BackendCommand("deploy:remove").run();
        ApiStorage.getConfigurationVariableStorage().setProperty("webdriver.base.url", null);
        System.setProperty("api.staging", null);
    }
}
