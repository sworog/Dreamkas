package ru.dreamkas.console.backend;

import org.openqa.jetty.util.UnixCrypt;
import ru.dreamkas.apiStorage.ApiStorage;

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
        String projectId = UnixCrypt.crypt(email, "user").replace(".", "").replace("/", "");
        ApiStorage.getUserVariableStorage().setUserProjectName(projectId);
        return projectId;
    }
}
