package ru.dreamkas.jbehave.deprecated.email;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import ru.dreamkas.steps.deprecated.email.EmailSteps;

import javax.mail.MessagingException;

public class EmailUserSteps {

    @Steps
    EmailSteps emailSteps;

    @Given("the user prepares email inbox")
    public void givenTheUserPreparesEmailInbox() {
        emailSteps.deleteAllMessages();
    }

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

    @Then("the user assert the email message content matches the required template")
    public void thenTheUserAssertsTheEmailMessageContentValue() {
        emailSteps.matchEmailMessageContentToSignUpRegexPattern();
    }

    @Then("the user assert the restore password email message content matches the required template")
    public void thenTheUserAssertsTheRestorePasswordEmailMessageContentMatchesTheRequiredTemplate() {
        emailSteps.matchEmailContentToRestorePasswordRegexPattern();
    }
}
