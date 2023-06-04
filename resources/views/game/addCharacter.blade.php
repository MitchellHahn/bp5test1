@if ($step === 1)
    <form method="post" action="">
        <input type="text" name="current_node" />
        <h2>Add a new question</h2>

        <input type="text" name="question" required />

        <div>
                <button type="submit">Add question</button>
        </div>
    </form>

    @elseif ($step === 2)
    <form method="post" action="">
        <input type="text" name="current_node" />
        <h2>Add a new question that distinguishes the character</h2>

        <input type="text" name="question" placeholder="question" required />
        <input type="text" name="answer" placeholder="answer" required />

        <div>
            <button type="submit">Add question</button>
        </div>
    </form>
@endif
