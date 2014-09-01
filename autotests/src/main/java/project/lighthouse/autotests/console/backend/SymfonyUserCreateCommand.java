package project.lighthouse.autotests.console.backend;

import org.apache.commons.codec.digest.UnixCrypt;

/**
 * symfony:user:create cap command implementation
 */
public class SymfonyUserCreateCommand extends BackendCommand {

    public SymfonyUserCreateCommand(String email, String password, String customProjectName) {
        super(String.format("symfony:user:create -S email=%s -S userpass=%s -S custom_project_name=%s", email, password, customProjectName));
    }

    public SymfonyUserCreateCommand(String email, String password) {
        this(email, password, userProjectId(email));
    }

    private static String userProjectId(String email) {
        return UnixCrypt.crypt(email).replace(".", "").replace("/", "");
    }
}
