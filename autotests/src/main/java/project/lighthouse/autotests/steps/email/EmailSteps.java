package project.lighthouse.autotests.steps.email;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.handler.email.EmailHandler;
import project.lighthouse.autotests.handler.email.EmailMessage;

import javax.mail.MessagingException;

import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class EmailSteps extends ScenarioSteps {

    private EmailMessage emailMessage;

    @Step
    public void getEmailMessage() throws MessagingException, InterruptedException {
        emailMessage = new EmailHandler().getEmailMessage();
    }

    @Step
    public void assertEmailMessageFrom(String expectedFrom) {
        assertThat(emailMessage.getFrom(), is(expectedFrom));
    }

    @Step
    public void assertEmailMessageSubject(String expectedSubject) {
        assertThat(emailMessage.getSubject(), is(expectedSubject));
    }

    @Step
    public void assertEmailMessageContent(String expectedContent) {
        assertThat(emailMessage.getContent(), containsString(expectedContent));
    }
}
