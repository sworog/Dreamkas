package project.lighthouse.autotests.jbehave.email;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.email.EmailSteps;

import javax.mail.MessagingException;

public class EmailUserSteps {

    @Steps
    EmailSteps emailSteps;

    @When("the user gets the last email message from the test email inbox folder")
    public void whenTheUserGetsTheLastEmailMessageFromTheTesEmailInboxFolder() throws MessagingException, InterruptedException {
        emailSteps.getEmailMessage();
    }

    @Then("the user assert the email message from value is '$expectedFrom'")
    public void thenTheUserAssertsTheEmailMessageFromValue(String expectedFrom) {
        emailSteps.assertEmailMessageFrom(expectedFrom);
    }

    @Then("the user assert the email message subject value is '$expectedSubject'")
    public void thenTheUserAssertsTheEmailMessageSubjectValue(String expectedSubject) {
        emailSteps.assertEmailMessageSubject(expectedSubject);
    }

    @Then("the user assert the email message content value contains '$expectedContent'")
    public void thenTheUserAssertsTheEmailMessageContentValue(String expectedContent) {
        emailSteps.assertEmailMessageContent(expectedContent);
    }
}
