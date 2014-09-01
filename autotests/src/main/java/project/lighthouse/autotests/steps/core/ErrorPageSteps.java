package project.lighthouse.autotests.steps.core;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.core.ErrorPage;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class ErrorPageSteps extends ScenarioSteps {

    ErrorPage errorPage;

    @Step
    public void assertH1Text(String text) {
        assertThat(errorPage.getH1Text(), is(text));
    }
}
