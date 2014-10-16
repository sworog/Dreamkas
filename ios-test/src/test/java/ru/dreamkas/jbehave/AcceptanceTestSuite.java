package ru.dreamkas.jbehave;

import net.thucydides.jbehave.ThucydidesJUnitStories;
import ru.dreamkas.apiStorage.ApiStorage;

import static net.thucydides.core.ThucydidesSystemProperty.WEBDRIVER_BASE_URL;

public class AcceptanceTestSuite extends ThucydidesJUnitStories {

    public AcceptanceTestSuite() {
        setWebDriverBaseUrl();
    }

    private void setWebDriverBaseUrl() {
        String baseUrl = getSystemConfiguration().getBaseUrl();
        getEnvironmentVariables().setProperty(WEBDRIVER_BASE_URL.getPropertyName(), baseUrl);
        getSystemConfiguration().getEnvironmentVariables().setProperty(WEBDRIVER_BASE_URL.getPropertyName(), baseUrl);
        ApiStorage.getConfigurationVariableStorage().setProperty("webdriver.base.url", baseUrl);
    }
}
