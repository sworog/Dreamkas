package project.lighthouse.autotests.steps.email;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.handler.email.EmailHandler;
import project.lighthouse.autotests.handler.email.EmailMessage;

import javax.mail.MessagingException;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class EmailSteps extends ScenarioSteps {

    private EmailMessage emailMessage;

    private static final String REGEX_PATTERN = "Добро пожаловать в Lighthouse!\r\n\r\nВаш пароль для входа: (.*)\r\nЕсли это письмо пришло вам по ошибке, просто проигнорируйте его.\r\n";

    @Step
    public void deleteAllMessages() {
        new EmailHandler().deleteAllMessages();
    }

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
    public void assertEmailMessageContent() {
        String message = String.format("the email message content is not matching the template. email message content: '%s'", emailMessage.getContent());
        assertThat(message, true, is(getMatcher().matches()));
    }

    @Step
    public String getEmailCredentials() {
        Matcher matcher = getMatcher();
        if (matcher.matches()) {
            return matcher.group(1);
        } else {
            String message = String.format("Cannot get password from the email message because email content is not matching. email content: '%s'", emailMessage.getContent());
            throw new AssertionError(message);
        }
    }

    private Matcher getMatcher() {
        Pattern pattern = Pattern.compile(REGEX_PATTERN);
        return pattern.matcher(emailMessage.getContent());
    }
}
