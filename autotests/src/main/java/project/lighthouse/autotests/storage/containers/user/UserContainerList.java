package project.lighthouse.autotests.storage.containers.user;

import java.util.ArrayList;

public class UserContainerList extends ArrayList<UserContainer> {

    public UserContainer getContainer(String email) {
        for (UserContainer userContainer : this) {
            if (userContainer.getEmail().equals(email)) {
                return userContainer;
            }
        }
        String message = String.format("No such user container with email '%s'", email);
        throw new AssertionError(message);
    }
}
