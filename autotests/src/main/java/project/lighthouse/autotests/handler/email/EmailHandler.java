package project.lighthouse.autotests.handler.email;

import javax.mail.*;
import java.util.Properties;

import static junit.framework.Assert.fail;

/**
 * Email handler class to interact with email
 */
public class EmailHandler {

    private EmailMessage emailMessage;
    private Store store;

    private String userName = System.getProperty("email.username");
    private String password = System.getProperty("email.password");

    public EmailHandler() {
        connect();
    }

    private void connect() {
        Properties props = new Properties();
        props.setProperty("mail.store.protocol", "imaps");
        try {
            Session session = Session.getInstance(props, null);
            store = session.getStore();
            store.connect("imap.gmail.com", userName, password);
        } catch (Exception e) {
            throw new AssertionError(e);
        }
    }

    private void setEmailMessage() {
        try {
            Folder inbox = store.getFolder("INBOX");
            inbox.open(Folder.READ_WRITE);
            Message msg = inbox.getMessage(inbox.getMessageCount());
            Multipart mp = (Multipart) msg.getContent();
            BodyPart bp = mp.getBodyPart(0);
            StringBuilder stringBuilder = new StringBuilder();
            for (Address value : msg.getFrom()) {
                stringBuilder.append(value);
            }
            emailMessage = new EmailMessage(stringBuilder.toString(), msg.getSubject(), bp.getContent().toString());
            msg.setFlag(Flags.Flag.DELETED, true);
            inbox.close(true);
        } catch (Exception e) {
            throw new AssertionError(e);
        }
    }

    private void waitForMessageToGet() throws MessagingException, InterruptedException {
        Folder inbox = store.getFolder("INBOX");
        inbox.open(Folder.READ_ONLY);
        int messageCount = inbox.getMessageCount();
        int count = 0;
        while (messageCount == 0 && count < 61) {
            messageCount = inbox.getMessageCount();
            Thread.sleep(1000);
            count++;
        }
        if (messageCount == 0 && count == 61) {
            fail("The email inbox folder is still empty after timeOut");
        }
        inbox.close(true);
    }

    public EmailMessage getEmailMessage() throws MessagingException, InterruptedException {
        waitForMessageToGet();
        setEmailMessage();
        return emailMessage;
    }
}
